export interface ApiSuccessResponse<T> {
    status: 'success';
    data: T;
  }
  
  export interface ApiErrorResponse {
    status: 'error';
    message: string;
  }
  
  export interface ApiListResponse<T> extends PaginatedResult<T> {
    status: 'success';
  }

  export interface PaginatedResult<T> {
    data: T[];
    total: number;
  }
  
  export interface FilmApiData {
    id: string;
    title: string;
    episode_id: number;
    opening_crawl: string;
    director: string;
    producer: string;
    release_date: string;
    characters: Array<{id: string, name?: string}>;
  }
  
  export interface PersonApiData {
    id: string;
    name: string;
    height: string;
    mass: string;
    hair_color: string;
    skin_color: string;
    eye_color: string;
    birth_year: string;
    gender: string;
    films: Array<{id: string, title?: string}>;
  }