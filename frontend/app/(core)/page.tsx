import MusicList from "@/components/music-list"
import AddMusicForm from "@/components/add-music-form"

export default function Home() {
  return (
    <div className="space-y-8">
      <h1 className="text-3xl font-bold">Music Library</h1>
      <AddMusicForm />
      <MusicList />
    </div>
  )
}

