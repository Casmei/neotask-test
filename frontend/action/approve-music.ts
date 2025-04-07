"use server"

import { revalidatePath, revalidateTag } from "next/cache"
import { musicApprove } from "@/api-request/api"

export async function approveMusicServerAction(data: {
    musicId: number,
    accessToken: string
}) {
    const response = await musicApprove(
        data.musicId,
        {
            headers: {
                Authorization: `Bearer ${data.accessToken}`
            }
        }
    )

    revalidateTag("pendingMusic");
    revalidatePath("/admin/pending");
    return response
}
