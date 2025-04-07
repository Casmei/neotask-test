import type React from "react";
import Navbar from "@/components/navbar";
import Provider from "../context/client-provider";

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <Provider>
      <Navbar />
      <main className="container mx-auto px-4 py-8">{children}</main>
    </Provider>
  );
}
