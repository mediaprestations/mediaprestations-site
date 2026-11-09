import { defineCollection } from 'astro:content';
import { glob } from 'astro/loaders';
import { z } from 'astro/zod';

export const CATEGORIES = [
  'Magazine',
  'Livre',
  'Catalogue & guide',
  'Fédérations & associations',
  'Créa & plaquette',
] as const;

const realisations = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/realisations' }),
  schema: ({ image }) =>
    z.object({
      titre: z.string(),
      numero: z.string().optional().default(''),
      categorie: z.enum(CATEGORIES).default('Magazine'),
      couverture: image(),
      date: z.coerce.date(),
      accueil: z.boolean().default(false),
      flipbook: z.string().optional().default(''),
    }),
});

export const collections = { realisations };
