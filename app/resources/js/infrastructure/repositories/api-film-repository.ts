import { ApiSuccessResponse, ApiListResponse } from "@/domain/entities";
import { Film, FilmApiData, PaginatedResult } from "@/domain/entities";
import { FilmRepository } from "@/application/interfaces";
import { HttpService } from "@/infrastructure/http";

export class ApiFilmRepository implements FilmRepository {

  constructor(private readonly httpService: HttpService) {}

  async getAll(query?: string): Promise<PaginatedResult<Film>> {
    try {
      const params: Record<string, string> = {};
      if (query) {
        params.q = query;
      }
      
      const response = await this.httpService.get<ApiListResponse<FilmApiData>>('/films', params);
      
      if (response.status === 'success') {
        return {
          data: response.data.map(filmData => Film.fromApi(filmData)),
          total: response.total
        };
      } else {
        throw new Error('Error fetching films');
      }
    } catch (error) {
      console.error('ApiFilmRepository.getAll error:', error);
      throw error;
    }
  }


  async getById(id: string): Promise<Film> {
    try {
      const response = await this.httpService.get<ApiSuccessResponse<FilmApiData>>(`/films/${id}`);
      
      if (response.status === 'success') {
        return Film.fromApi(response.data);
      } else {
        throw new Error(`Film with ID ${id} not found`);
      }
    } catch (error) {
      console.error(`ApiFilmRepository.getById(${id}) error:`, error);
      throw error;
    }
  }
}
