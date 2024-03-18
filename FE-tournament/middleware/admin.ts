import {useAuthStore} from "~/stores/useAuthStore";

export default defineNuxtRouteMiddleware((to, from) => {
  const auth = useAuthStore();

  if (auth.user?.user_type !== 'admin') {
    return navigateTo("/forbidden", {replace: true});
  }
})