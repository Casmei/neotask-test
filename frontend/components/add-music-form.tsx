"use client"

import { useState } from "react"
import { useSession } from "next-auth/react"
import { z } from "zod"
import { useForm } from "react-hook-form"
import { zodResolver } from "@hookform/resolvers/zod"
import { Music, Youtube, AlertCircle } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { addMusicServerAction } from "@/action/add-music"


const formSchema = z.object({
  youtubeUrl: z.string().url("Invalid URL").refine((val) =>
    /^https?:\/\/(www\.)?(youtube\.com|music\.youtube\.com|youtu\.be)\//.test(val), {
    message: "Please enter a valid YouTube URL",
  })
})

type FormData = z.infer<typeof formSchema>

export default function AddMusicForm() {
  const { data: session, status } = useSession()
  const [success, setSuccess] = useState("")

  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
    reset
  } = useForm<FormData>({
    resolver: zodResolver(formSchema)
  })


  const onSubmit = async (data: FormData) => {
    setSuccess("")

    if (!session) return

    try {
      const response = await addMusicServerAction({
        youtube_url: data.youtubeUrl,
        accessToken: session?.accessToken ?? ""
      })

      if (response.status == 201) {
        const message = "Música adicionada com sucesso!";
        const approvalMessage = session.user?.isAdmin ? "" : " Será analisado por um administrador.";
        setSuccess(`${message} ${approvalMessage}`)
      }

      reset();
    } catch (err) {
      console.error("Error:", err)
    }
  }

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center">
          <Music className="h-5 w-5 mr-2" />
          Adicionar nova música
        </CardTitle>
        <CardDescription>Cole a URL do YouTube para adicionar uma nova música à biblioteca</CardDescription>
      </CardHeader>
      <CardContent>
        {errors.youtubeUrl && (
          <Alert variant="destructive" className="mb-4">
            <AlertCircle className="h-4 w-4" />
            <AlertDescription>{errors.youtubeUrl.message}</AlertDescription>
          </Alert>
        )}

        {success && (
          <Alert className="mb-4 bg-green-50 text-green-800 border-green-200">
            <AlertDescription>{success}</AlertDescription>
          </Alert>
        )}

        <form onSubmit={handleSubmit(onSubmit)} className="flex flex-col sm:flex-row gap-2">
          <div className="relative flex-1">
            <Youtube className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input
              placeholder="https://www.youtube.com/watch?v=..."
              {...register("youtubeUrl")}
              className="pl-9"
              disabled={isSubmitting}
            />
          </div>
          <Button type="submit" disabled={isSubmitting || !session}>
            {isSubmitting ? "Adicionando..." : "Adicionar música"}
          </Button>
        </form>

        {status !== "loading" && !session && (
          <div className="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-md">
            <p className="text-amber-800">Realizar login para adicionar música à biblioteca</p>
          </div>
        )}
      </CardContent>
    </Card>
  )
}
