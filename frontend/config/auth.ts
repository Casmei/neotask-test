import { authLogin } from "@/api-request/api";
import { loginSchema } from "@/validations/auth";
import NextAuth, { User } from "next-auth";
import { JWT } from "next-auth/jwt";
import Credentials from "next-auth/providers/credentials";



export const { handlers, signIn, signOut, auth } = NextAuth({
  providers: [
    Credentials({
      credentials: {
        email: { type: "email" },
        password: { type: "password" },
      },
      authorize: async (credentials) => {
        const { email: formEmail, password: formPassword } = await loginSchema.parseAsync(credentials)

        const { id, email, isAdmin, name, token } = await authLogin({
          email: formEmail,
          password: formPassword
        });


        return { id, email, isAdmin, name, token }
      },
    }),
  ],
  pages: {
    signIn: "/login",
  },
  callbacks: {
    async jwt({ token: initialToken, user, account }) {
      const token = initialToken as JWT;
      if (account && user) {
        token.accessToken = user.token;
        token.user = user;
      }

      return token;
    },

    async session({ session, token }) {
      session.user = token.user as any;
      session.accessToken = token.accessToken as any;

      return session;
    },
  },
});

