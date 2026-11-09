import { defineConfig } from 'astro/config';

export default defineConfig({
  site: 'https://mediaprestations.fr',
  trailingSlash: 'ignore',
  build: { format: 'directory' },
});
