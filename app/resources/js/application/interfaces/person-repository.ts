import { Person, PaginatedResult } from '@/domain/entities';


export interface PersonRepository {
  getAll(query?: string): Promise<PaginatedResult<Person>>;
  getById(id: string): Promise<Person>;
}