export default defineNuxtRouteMiddleware(() => {
  const token = useCookie<string | null>('token')
  if (!token.value) return navigateTo('/login')
})