"use client"

import { useEffect, useState } from "react"
import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Skeleton } from "@/components/ui/skeleton"
import { Check } from 'lucide-react'
import MusicCard from "./music-card"
import Pagination from "./pagination"
import { musicGetPendingMusics } from "@/api-request/api"
import { useSession } from "next-auth/react"
import { approveMusicServerAction } from "@/action/approve-music"

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

export default function PendingMusicList({
  onError,
}: {
  onError: (error: string) => void
}) {
  const { data: session, status } = useSession()
  const [pendingMusics, setPendingMusics] = useState<Music[]>([])
  const [meta, setMeta] = useState<PaginationMeta | null>(null)
  const [loading, setLoading] = useState(true)
  const [currentPage, setCurrentPage] = useState(1)

  const fetchPendingMusics = async (page = 1) => {
    setLoading(true)

    try {
      const response = await musicGetPendingMusics({ page, orderBy: "views" }, {
        headers: {
          Authorization: `Bearer ${session?.accessToken}`
        },
        next: {
          tags: ['pendingMusic']
        },

      }).catch((err) => {
        console.log("Network error:", err)
        return null
      })

      if (!response || response.status !== 200) {
        onError("Failed to load music list. Please try again later.")
        setPendingMusics([])
        setMeta(null)
        return
      }

      const data = response.data as MusicResponse

      setPendingMusics(data.data)
      setMeta(data.meta)
      setCurrentPage(Number.parseInt(data.meta.current_page.toString()))
    } catch (err) {
      console.error("Error fetching musics:", err)
      setPendingMusics([])
      setMeta(null)
    } finally {
      setLoading(false)
    }
  };

  useEffect(() => {
    if (session?.accessToken) {
      fetchPendingMusics()
    }
  }, [session])

  const handlePageChange = (page: number) => {
    fetchPendingMusics(page)
  }

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

  if (loading) {
    return (
      <div className="space-y-4">
        {[1, 2, 3].map((i) => (
          <Card key={i}>
            <CardContent className="p-4">
              <div className="flex space-x-4">
                <Skeleton className="h-24 w-24 rounded-md" />
                <div className="space-y-2 flex-1">
                  <Skeleton className="h-6 w-3/4" />
                  <Skeleton className="h-4 w-1/2" />
                  <Skeleton className="h-4 w-1/4" />
                </div>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>
    )
  }

  return (
    <div className="space-y-6">
      {pendingMusics.length === 0 ? (
        <div className="text-center py-10">
          <p className="text-muted-foreground">No pending music found</p>
        </div>
      ) : (
        <>
          <div className="space-y-4">
            {pendingMusics.map((music) => (
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

          {meta && (
            <Pagination
              currentPage={currentPage}
              totalPages={Number.parseInt(meta.last_page)}
              onPageChange={handlePageChange}
            />
          )}
        </>
      )}
    </div>
  )
}

