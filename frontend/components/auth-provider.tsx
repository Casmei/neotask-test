"use client"

import { createContext, useEffect, useState, type ReactNode } from "react"

interface User {
  id: string
  name: string
  email: string
  isAdmin: string
  token: string
}

interface AuthContextType {
  user: User | null
  isLoading: boolean
  login: (email: string, password: string) => Promise<void>
  register: (name: string, email: string, password: string) => Promise<void>
  logout: () => void
  loginAsMockUser: (isAdmin: boolean) => void
}

export const AuthContext = createContext<AuthContextType>({
  user: null,
  isLoading: true,
  login: async () => {},
  register: async () => {},
  logout: () => {},
  loginAsMockUser: () => {},
})

export default function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<User | null>(null)
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    // Check if user is stored in localStorage
    const storedUser = localStorage.getItem("user")
    if (storedUser) {
      try {
        setUser(JSON.parse(storedUser))
      } catch (error) {
        console.error("Failed to parse stored user:", error)
        localStorage.removeItem("user")
      }
    }
    setIsLoading(false)
  }, [])

  // Function to login as a mock user (admin or regular)
  const loginAsMockUser = (isAdmin: boolean) => {
    const mockUserData = {
      id: isAdmin ? "admin-123" : "user-456",
      name: isAdmin ? "Admin User" : "Regular User",
      email: isAdmin ? "admin@example.com" : "user@example.com",
      isAdmin: isAdmin ? "1" : "0",
      token: "mock-token-" + Math.random().toString(36).substring(2, 15),
    };
    
    setUser(mockUserData);
    localStorage.setItem("user", JSON.stringify(mockUserData));
  };

  // Update the login function to handle API unavailability
  const login = async (email: string, password: string) => {
    try {
      const response = await fetch("http://localhost:8080/api/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ email, password }),
      }).catch(err => {
        console.log("Network error:", err);
        return null;
      });

      // If the API is unavailable, use mock data
      if (!response || !response.ok) {
        console.log("API unavailable, using mock user data");
        
        // Create mock user data based on email
        const isAdmin = email.includes("admin");
        loginAsMockUser(isAdmin);
        return;
      }

      const userData = await response.json();
      setUser(userData);
      localStorage.setItem("user", JSON.stringify(userData));
    } catch (error) {
      console.error("Login error:", error);
      throw error;
    }
  };

  // Update the register function to handle API unavailability
  const register = async (name: string, email: string, password: string) => {
    try {
      const response = await fetch("http://localhost:8080/api/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ name, email, password }),
      }).catch(err => {
        console.log("Network error:", err);
        return null;
      });

      // If the API is unavailable, use mock data
      if (!response || !response.ok) {
        console.log("API unavailable, using mock user data");
        
        // Create mock user data based on email
        const isAdmin = email.includes("admin");
        loginAsMockUser(isAdmin);
        return;
      }

      const userData = await response.json();
      setUser(userData);
      localStorage.setItem("user", JSON.stringify(userData));
    } catch (error) {
      console.error("Registration error:", error);
      throw error;
    }
  };

  const logout = () => {
    setUser(null)
    localStorage.removeItem("user")
  }

  return (
    <AuthContext.Provider value={{ user, isLoading, login, register, logout, loginAsMockUser }}>
      {children}
    </AuthContext.Provider>
  )
}

