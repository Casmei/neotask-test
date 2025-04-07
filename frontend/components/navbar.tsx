"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useAuth } from "@/hooks/use-auth";
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

export default function Navbar() {
  const { user, logout, loginAsMockUser } = useAuth();
  const pathname = usePathname();

  return (
    <header className="bg-background border-b sticky top-0 z-10">
      <div className="container mx-auto px-4 py-3 flex items-center justify-between">
        <div className="flex items-center space-x-2">
          <Music className="h-6 w-6 text-primary" />
          <Link href="/" className="text-xl font-bold">
            Music App
          </Link>
        </div>

        <nav className="hidden md:flex items-center space-x-6">
          <Link
            href="/"
            className={`hover:text-primary transition-colors ${
              pathname === "/" ? "text-primary font-medium" : ""
            }`}
          >
            Home
          </Link>
          {user?.isAdmin === "1" && (
            <Link
              href="/admin/pending"
              className={`hover:text-primary transition-colors ${
                pathname === "/admin/pending" ? "text-primary font-medium" : ""
              }`}
            >
              Pending Approval
            </Link>
          )}
        </nav>

        <div className="flex items-center space-x-2">
          {user ? (
            <>
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="outline" size="sm">
                    <UserCircle className="h-4 w-4 mr-2" />
                    {user.name}
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuLabel>Account</DropdownMenuLabel>
                  <DropdownMenuSeparator />
                  {user.isAdmin === "1" && (
                    <DropdownMenuItem asChild>
                      <Link href="/admin/pending">
                        <ClipboardList className="h-4 w-4 mr-2" />
                        Pending Approval
                      </Link>
                    </DropdownMenuItem>
                  )}
                  <DropdownMenuItem onClick={logout}>
                    <LogOut className="h-4 w-4 mr-2" />
                    Logout
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </>
          ) : (
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
                  <DropdownMenuItem onClick={() => loginAsMockUser(true)}>
                    Login as Admin
                  </DropdownMenuItem>
                  <DropdownMenuItem onClick={() => loginAsMockUser(false)}>
                    Login as Regular User
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
                  Register
                </Link>
              </Button>
            </>
          )}
        </div>
      </div>
      
      {/* Mobile navigation for admin */}
      {user?.isAdmin === "1" && (
        <div className="md:hidden border-t">
          <div className="container mx-auto px-4 py-2">
            <Link
              href="/admin/pending"
              className={`flex items-center py-2 ${
                pathname === "/admin/pending" ? "text-primary font-medium" : ""
              }`}
            >
              <ClipboardList className="h-4 w-4 mr-2" />
              Pending Music Approval
            </Link>
          </div>
        </div>
      )}
    </header>
  );
}

