// app/action/register.ts
'use server'

import { authRegister } from "@/api-request/api"
import { z } from "zod"

const schema = z.object({
    name: z.string(),
    email: z.string().email(),
    password: z.string().min(6),
})

export const registerAction = async (data: any) => {
    const parsed = schema.safeParse(data)
    if (!parsed.success) {
        return { error: "Dados inválidos." }
    }

    try {
        await authRegister(parsed.data)
        return { success: true }
    } catch (e) {
        return { error: "Erro ao criar usuário." }
    }
}
