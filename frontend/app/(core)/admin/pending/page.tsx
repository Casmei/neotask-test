"use client";

import { useEffect, useState } from "react";
import { useAuth } from "@/hooks/use-auth";
import { useRouter } from "next/navigation";
import PendingMusicList from "@/components/pending-music-list";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { AlertCircle, ShieldAlert } from 'lucide-react';
import { Button } from "@/components/ui/button";

export default function PendingMusicsPage() {
  const router = useRouter();
  const [error, setError] = useState("");

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold">Músicas pendentes de aprovação</h1>
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

