import { PaginatedResult, Film } from "@/domain/entities";
import { FilmRepository } from "@/application/interfaces";


export class GetFilmsUseCase {
  constructor(private readonly repository: FilmRepository) {}


  async execute(query?: string): Promise<PaginatedResult<Film>> {
    try {
      const result = await this.repository.getAll(query);
      
      return result;
    } catch (error) {
      console.error('GetFilmsUseCase error:', error);
      throw error;
    }
  }
}
