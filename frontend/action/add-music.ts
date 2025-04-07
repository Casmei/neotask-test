"use server"

import { revalidateTag } from "next/cache"
import { musicStore } from "@/api-request/api"

export async function addMusicServerAction(data: {
    youtube_url: string
    accessToken: string
}) {
    const response = await musicStore(
        { youtube_url: data.youtube_url },
        {
            headers: {
                Authorization: `Bearer ${data.accessToken}`
            }
        }
    )

    revalidateTag("musics")
    return response
}
