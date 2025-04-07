"use client";

import { useEffect, useState } from "react";
import { useAuth } from "@/hooks/use-auth";
import { useRouter } from "next/navigation";
import PendingMusicList from "@/components/pending-music-list";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { AlertCircle, ShieldAlert } from 'lucide-react';
import { Button } from "@/components/ui/button";

export default function PendingMusicsPage() {
  const { user, isLoading, loginAsMockUser } = useAuth();
  const router = useRouter();
  const [error, setError] = useState("");

  useEffect(() => {
    if (!isLoading && (!user || user.isAdmin !== "1")) {
      // If not admin, show error but don't redirect immediately
      setError("You need admin privileges to access this page");
    }
  }, [user, isLoading, router]);

  if (isLoading) {
    return <div className="flex justify-center py-10">Loading...</div>;
  }

  if (!user) {
    return (
      <div className="space-y-6">
        <h1 className="text-3xl font-bold">Admin Access Required</h1>
        <Alert variant="destructive">
          <AlertCircle className="h-4 w-4" />
          <AlertDescription>You need to be logged in as an admin to access this page</AlertDescription>
        </Alert>
        <div className="flex flex-col sm:flex-row gap-4">
          <Button onClick={() => loginAsMockUser(true)}>
            Login as Mock Admin
          </Button>
          <Button variant="outline" onClick={() => router.push("/login")}>
            Go to Login
          </Button>
        </div>
      </div>
    );
  }

  if (user.isAdmin !== "1") {
    return (
      <div className="space-y-6">
        <h1 className="text-3xl font-bold">Admin Access Required</h1>
        <div className="p-6 bg-amber-50 border border-amber-200 rounded-lg">
          <div className="flex items-start">
            <ShieldAlert className="h-6 w-6 text-amber-600 mr-3 mt-0.5" />
            <div>
              <h3 className="text-lg font-medium text-amber-800">Access Denied</h3>
              <p className="text-amber-700 mt-1">
                Your account doesn't have admin privileges. Only administrators can access the pending music approval page.
              </p>
              <div className="mt-4">
                <Button onClick={() => loginAsMockUser(true)}>
                  Switch to Mock Admin Account
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold">Pending Music Approval</h1>
      {error && (
        <Alert variant="destructive">
          <AlertCircle className="h-4 w-4" />
          <AlertDescription>{error}</AlertDescription>
        </Alert>
      )}
      <PendingMusicList onError={setError} />
    </div>
  );
}

