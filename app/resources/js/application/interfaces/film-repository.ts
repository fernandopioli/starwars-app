import { Film, PaginatedResult } from '@/domain/entities';


export interface FilmRepository {
  getAll(query?: string): Promise<PaginatedResult<Film>>;
  getById(id: string): Promise<Film>;
}