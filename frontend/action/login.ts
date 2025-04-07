'use server';

import { redirect } from 'next/navigation';
import { AuthError } from 'next-auth';
import { signIn } from '@/config/auth';

export const loginAction = async (data: FormData) => {
    try {
        await signIn('credentials', {
            email: data.get('email') as string,
            password: data.get('password') as string,
            redirect: true,
            redirectTo: "/"
        });
    } catch (error) {
        if (error instanceof AuthError) {
            console.log(error.type);
        }
    }
    redirect('/');
};