import { FilmApiData } from './api.types';


export class Film {
  readonly id: string;
  readonly title: string;
  readonly episodeId: number;
  readonly openingCrawl: string;
  readonly director: string;
  readonly producer: string;
  readonly releaseDate: string;
  readonly characters: string[];

  private constructor({
    id,
    title,
    episodeId,
    openingCrawl,
    director,
    producer,
    releaseDate,
    characters = [],
  }: {
    id: string;
    title: string;
    episodeId: number;
    openingCrawl: string;
    director: string;
    producer: string;
    releaseDate: string;
    characters?: string[];
  }) {
    this.id = id;
    this.title = title;
    this.episodeId = episodeId;
    this.openingCrawl = openingCrawl;
    this.director = director;
    this.producer = producer;
    this.releaseDate = releaseDate;
    this.characters = characters;
  }

  static fromApi(data: FilmApiData): Film {
    return new Film({
      id: data.id,
      title: data.title,
      episodeId: data.episode_id,
      openingCrawl: data.opening_crawl,
      director: data.director,
      producer: data.producer,
      releaseDate: data.release_date,
      characters: data.characters || [],
    });
  }

  getCharacterIds(): string[] {
    return this.characters.map(url => {
      const parts = url.split('/');
      return parts[parts.length - 2]; // Pega o ID da URL
    });
  }

  getFormattedReleaseDate(): string {
    const date = new Date(this.releaseDate);
    return date.toLocaleDateString('pt-BR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  }
}

export default Film;