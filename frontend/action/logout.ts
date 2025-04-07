'use server';

import { redirect } from 'next/navigation';
import { signOut } from 'next-auth/react';

export const logoutAction = async () => {
    await signOut();
    redirect('/');
};