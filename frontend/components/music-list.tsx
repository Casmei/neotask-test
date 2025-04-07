"use client"

import { useEffect, useState } from "react"
import { Card, CardContent } from "@/components/ui/card"
import { Skeleton } from "@/components/ui/skeleton"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { AlertCircle } from "lucide-react"
import MusicCard from "./music-card"
import Pagination from "./pagination"
import { musicIndex } from "@/api-request/api"

interface Music {
  id: string
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

export default function MusicList() {
  const [musics, setMusics] = useState<Music[]>([])
  const [meta, setMeta] = useState<PaginationMeta | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState("")
  const [currentPage, setCurrentPage] = useState(1)

  const fetchMusics = async (page = 1) => {
    setLoading(true)
    setError("")

    try {
      const response = await musicIndex({ page, orderBy: "views" }, {
        next: {
          tags: ['musics']
        }
      }).catch((err) => {
        console.log("Network error:", err)
        return null
      })

      if (!response || response.status !== 200) {
        setError("Failed to load music list. Please try again later.")
        setMusics([])
        setMeta(null)
        return
      }

      const data = response.data as MusicResponse

      setMusics(data.data)
      setMeta(data.meta)
      setCurrentPage(Number.parseInt(data.meta.current_page.toString()))
    } catch (err) {
      console.error("Error fetching musics:", err)
      setMusics([])
      setMeta(null)
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchMusics()
  }, [])

  const handlePageChange = (page: number) => {
    fetchMusics(page)
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

  if (error) {
    return (
      <Alert variant="destructive">
        <AlertCircle className="h-4 w-4" />
        <AlertDescription>{error}</AlertDescription>
      </Alert>
    )
  }

  return (
    <div className="space-y-6">
      {musics.length === 0 ? (
        <div className="text-center py-10">
          <p className="text-muted-foreground">No music found</p>
        </div>
      ) : (
        <>
          <div className="space-y-4">
            {musics.map((music) => (
              <MusicCard key={music.id} music={music} />
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

