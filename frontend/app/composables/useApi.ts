export const useApi = () => {
  const config = useRuntimeConfig()
  const token = useCookie<string | null>('token')

  return <T>(path: string, options: any = {}) =>
    $fetch<T>(path, {
      baseURL: config.public.apiBase,
      ...options,
      headers: {
        Accept: 'application/json',
        ...(token.value ? { Authorization: `Bearer ${token.value}` } : {}),
        ...options.headers,
      },
    })
}