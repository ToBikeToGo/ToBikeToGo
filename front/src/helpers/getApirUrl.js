export const getApirUrl = () => {
  const env = import.meta.env;

  return env.VITE_API_URL;
};
