"use client"

import { useEffect, useState } from "react"
import { Card, CardContent } from "@/components/ui/card"
import { Skeleton } from "@/components/ui/skeleton"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { AlertCircle } from "lucide-react"
import MusicCard from "./music-card"
import Pagination from "./pagination"

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
      // Try to fetch from the API
      const response = await fetch(`http://localhost:8080/api/musics?page=${page}`, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      }).catch((err) => {
        console.log("Network error:", err)
        return null
      })

      // If the fetch failed or returned an error status, use mock data
      if (!response || !response.ok) {
        console.log("Using mock data due to API unavailability")

        // Mock data for development/preview
        const mockData: MusicResponse = {
          data: [
            {
              id: "1",
              title: "Mock Music 1 - API Unavailable",
              youtube_id: "dQw4w9WgXcQ",
              formatted_views: "10M",
              thumbnail: "/placeholder.svg?height=200&width=200",
              url: "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
            },
            {
              id: "2",
              title: "Mock Music 2 - API Unavailable",
              youtube_id: "dQw4w9WgXcQ",
              formatted_views: "5M",
              thumbnail: "/placeholder.svg?height=200&width=200",
              url: "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
            },
            {
              id: "3",
              title: "Mock Music 3 - API Unavailable",
              youtube_id: "dQw4w9WgXcQ",
              formatted_views: "2M",
              thumbnail: "/placeholder.svg?height=200&width=200",
              url: "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
            },
          ],
          meta: {
            total: "3",
            current_page: page.toString(),
            per_page: "10",
            last_page: "1",
          },
        }

        setMusics(mockData.data)
        setMeta(mockData.meta)
        setCurrentPage(page)
        return
      }

      const data: MusicResponse = await response.json()
      setMusics(data.data)
      setMeta(data.meta)
      setCurrentPage(Number.parseInt(data.meta.current_page))
    } catch (err) {
      console.error("Error fetching musics:", err)
      setError("Failed to load music list. Please try again later.")

      // Set empty data instead of leaving previous data
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

