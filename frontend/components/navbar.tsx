"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { Button } from "@/components/ui/button";
import { Music, LogIn, UserPlus, LogOut, ClipboardList, UserCircle } from 'lucide-react';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { signIn, signOut, useSession } from "next-auth/react";

export default function Navbar() {
  const pathname = usePathname();
  const { data: session, status } = useSession();
  if (status === "loading") {
    return null;
  }
  const user = session?.user;


  const loginAsMockUser = async (isAdmin: boolean) => {
    await signIn("credentials", {
      email: isAdmin ? "admin@example.com" : "user@example.com",
      password: "password",
      callbackUrl: "/"
    });
  }

  return (
    <header className="bg-background border-b sticky top-0 z-10">
      <div className="container mx-auto px-4 py-3 flex items-center justify-between">
        <div className="flex items-center space-x-2">
          <Music className="h-6 w-6 text-primary" />
          <Link href="/" className="text-xl font-bold">
            Neotask
          </Link>
        </div>

        <nav className="hidden md:flex items-center space-x-6">
          <Link
            href="/"
            className={`hover:text-primary transition-colors ${pathname === "/" ? "text-primary font-medium" : ""
              }`}
          >
            Principal
          </Link>
          {user?.isAdmin && (
            <Link
              href="/admin/pending"
              className={`hover:text-primary transition-colors ${pathname === "/admin/pending" ? "text-primary font-medium" : ""
                }`}
            >
              Pendentes de aprovação
            </Link>
          )}
        </nav>

        <div className="flex items-center space-x-2">
          {status === "authenticated" ? (
            <>
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="outline" size="sm">
                    <UserCircle className="h-4 w-4 mr-2" />
                    {user?.name}
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuLabel>Account</DropdownMenuLabel>
                  <DropdownMenuSeparator />
                  {user?.isAdmin && (
                    <DropdownMenuItem asChild>
                      <Link href="/admin/pending">
                        <ClipboardList className="h-4 w-4 mr-2" />
                        Pendentes de aprovação
                      </Link>
                    </DropdownMenuItem>
                  )}

                  <DropdownMenuItem onClick={() => signOut({ callbackUrl: "/" })}>
                    <LogOut className="h-4 w-4 mr-2" />
                    Sair
                  </DropdownMenuItem>

                </DropdownMenuContent>
              </DropdownMenu>
            </>
          ) : status === "unauthenticated" ? (
            <>
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="outline" size="sm">
                    <UserCircle className="h-4 w-4 mr-2" />
                    Demo Login
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuLabel>Quick Login</DropdownMenuLabel>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem onClick={async () => await loginAsMockUser(true)}>
                    Login como Admin
                  </DropdownMenuItem>
                  <DropdownMenuItem onClick={async () => await loginAsMockUser(false)}>
                    Login como Usuário regular
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
              <Button variant="outline" size="sm" asChild>
                <Link href="/login">
                  <LogIn className="h-4 w-4 mr-2" />
                  Login
                </Link>
              </Button>
              <Button size="sm" asChild>
                <Link href="/register">
                  <UserPlus className="h-4 w-4 mr-2" />
                  Cadastro
                </Link>
              </Button>
            </>
          ) : null}
        </div>
      </div>

      {/* Mobile navigation for admin */}
      {
        user?.isAdmin && (
          <div className="md:hidden border-t">
            <div className="container mx-auto px-4 py-2">
              <Link
                href="/admin/pending"
                className={`flex items-center py-2 ${pathname === "/admin/pending" ? "text-primary font-medium" : ""
                  }`}
              >
                <ClipboardList className="h-4 w-4 mr-2" />
                Pendentes de aprovação
              </Link>
            </div>
          </div>
        )
      }
    </header >
  );
}

