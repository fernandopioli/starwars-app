import { FilmRepository } from "@/application/interfaces";
import { Film } from "@/domain/entities";

export class GetFilmByIdUseCase {
    constructor(private readonly repository: FilmRepository) {}

    async execute(id: string): Promise<Film> {
      try {
        const filmData = await this.repository.getById(id);
        return filmData;
      } catch (error) {
        console.error(`GetFilmByIdUseCase error:`, error);
        throw error;
      }
    }
  }