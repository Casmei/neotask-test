import { musicGetPendingMusics } from "@/api-request/api";
import PendingMusicList from "@/components/pending-music-list";
import { auth } from "@/config/auth";

async function getData(page: number) {
  const token = await auth();
  return musicGetPendingMusics({ page, orderBy: "views" }, {
    headers: {
      Authorization: `Bearer ${token?.accessToken}`
    },
    next: {
      tags: ['pendingMusic']
    },
    cache: 'force-cache',
  })
}

type Props = {
  params: Promise<{
    page: string
  }>
}

export default async function PendingMusicsPage({ params }: Props) {
  const { page = "1" } = await params;
  const teste = await getData(+page);

  if (teste.status !== 200) {
    return (
      <div className="flex items-center justify-center h-screen">
        <h1 className="text-3xl font-bold">Erro ao carregar músicas pendentes</h1>
      </div>
    )
  }

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold">Músicas pendentes de aprovação</h1>
      <PendingMusicList pendingMusics={teste.data} />
    </div>
  );
}