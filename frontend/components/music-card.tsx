"use client"

import Image from "next/image"
import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { ExternalLink, Eye } from "lucide-react"

interface Music {
  id: string
  title: string
  youtube_id: string
  formatted_views: string
  thumbnail: string
  url: string
}

export default function MusicCard({ music }: { music: Music }) {
  return (
    <Card className="overflow-hidden">
      <CardContent className="p-0">
        <div className="flex flex-col sm:flex-row">
          <div className="relative w-full sm:w-48 h-48 sm:h-auto">
            {music.thumbnail ? (
              <Image
                src={music.thumbnail || "/placeholder.svg"}
                alt={music.title}
                fill
                className="object-cover"
                onError={(e) => {
                  // If image fails to load, replace with placeholder
                  const target = e.target as HTMLImageElement
                  target.src = `/placeholder.svg?height=200&width=200`
                }}
              />
            ) : (
              <Image src={`/placeholder.svg?height=200&width=200`} alt={music.title} fill className="object-cover" />
            )}
          </div>
          <div className="p-4 flex flex-col justify-between flex-1">
            <div>
              <h3 className="text-lg font-semibold line-clamp-2">{music.title}</h3>
              <div className="flex items-center mt-2 text-sm text-muted-foreground">
                <Eye className="h-4 w-4 mr-1" />
                <span>{music.formatted_views} views</span>
              </div>
            </div>
            <div className="mt-4">
              <Button
                variant="outline"
                size="sm"
                className="w-full sm:w-auto"
                onClick={() => window.open(music.url, "_blank")}
              >
                <ExternalLink className="h-4 w-4 mr-2" />
                Watch on YouTube
              </Button>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  )
}

