const baseURL = 'http://localhost:8080/api';

export const customInstance = async <T>(
    url: string,
    {
        method,
        params,
        body,
        tags,
        cache = 'no-store',
        headers, // <- importante!
    }: {
        method: 'GET' | 'POST' | 'PUT' | 'DELETE' | 'PATCH';
        params?: Record<string, any>;
        body?: any;
        tags?: string[];
        cache?: RequestCache;
        headers?: HeadersInit;
    }
): Promise<T> => {
    let targetUrl = `${baseURL}${url}`;

    if (params) {
        targetUrl += '?' + new URLSearchParams(params).toString();
    }

    const response = await fetch(targetUrl, {
        method,
        body: body ? JSON.stringify(body) : undefined,
        headers: {
            'Content-Type': 'application/json',
            ...(headers || {}), // <- merge com os headers customizados
        },
        cache,
        next: tags ? { tags } : undefined,
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    return response.json();
};
