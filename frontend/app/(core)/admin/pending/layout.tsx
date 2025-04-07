import { auth } from "@/config/auth";
import { redirect } from "next/navigation";

export default async function RootLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const session = await auth();

    if (!session?.user?.isAdmin) {
        redirect('/login');
    }
    return <div>{children}</div>;
}
