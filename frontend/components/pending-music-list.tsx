"use client"

import { Card, CardContent } from "@/components/ui/card"
import { Check } from 'lucide-react'
import MusicCard from "./music-card"
import Pagination from "./pagination"
import { useSession } from "next-auth/react"
import { approveMusicServerAction } from "@/action/approve-music"
import { MusicGetPendingMusics200 } from "@/model"
import { useRouter } from "next/navigation"
import { Button } from "./ui/button"

interface Music {
  id: number
  title: string
  youtube_id: string
  formatted_views: string
  thumbnail: string
  url: string
}

interface PaginationMeta {
  total: string
  current_page: string
  per_page: string
  last_page: string
}

interface MusicResponse {
  data: Music[]
  meta: PaginationMeta
}

type Props = {
  pendingMusics: MusicGetPendingMusics200
}

export default function PendingMusicList({ pendingMusics }: Props) {
  const onError = (message: string) => {
    console.error(message)
  }
  const { data: session, status } = useSession()
  const router = useRouter()

  const approveMusic = async (musicId: number) => {
    if (status === "unauthenticated" || !session?.accessToken) {
      return
    }

    const accessToken = session.accessToken

    try {
      await approveMusicServerAction({ musicId, accessToken });
    } catch (err) {
      onError("Failed to approve music. Please try again.")
    }
  }

  const handlePageChange = async (page: number) => {
    router.push(`/admin/pending?page=${page}`)
  }

  return (
    <div className="space-y-6">
      {pendingMusics.data.length === 0 ? (
        <div className="text-center py-10">
          <p className="text-muted-foreground">No pending music found</p>
        </div>
      ) : (
        <>
          <div className="space-y-4">
            {pendingMusics.data.map((music) => (
              <Card key={music.id} className="overflow-hidden">
                <CardContent className="p-0">
                  <div className="flex flex-col sm:flex-row">
                    <div className="flex-1">
                      <MusicCard music={music} />
                    </div>
                    <div className="p-4 border-t sm:border-t-0 sm:border-l flex items-center justify-center">
                      <Button
                        onClick={() => approveMusic(music.id)}
                        className="w-full sm:w-auto"
                      >

                        <>
                          <Check className="h-4 w-4 mr-2" />
                          Approve
                        </>
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>

          {pendingMusics.meta.total > pendingMusics.meta.per_page && (
            <Pagination
              currentPage={+pendingMusics.meta.current_page}
              totalPages={Number.parseInt(pendingMusics.meta.last_page)}
              onPageChange={handlePageChange}
            />
          )}
        </>
      )}
    </div>
  )
}