import { ApiSuccessResponse, ApiListResponse } from "@/domain/entities";
import { Person, PersonApiData, PaginatedResult } from "@/domain/entities";
import { PersonRepository } from "@/application/interfaces";
import { HttpService } from "@/infrastructure/http";

export class ApiPersonRepository implements PersonRepository {

  constructor(private readonly httpService: HttpService) {}

  async getAll(query?: string): Promise<PaginatedResult<Person>> {
    try {
      const params: Record<string, string> = {};
      if (query) {
        params.q = query;
      }
      
      const response = await this.httpService.get<ApiListResponse<PersonApiData>>('/people', params);
      
      if (response.status === 'success') {
        return {
          data: response.data.map(personData => Person.fromApi(personData)),
          total: response.total
        };
      } else {
        throw new Error('Error fetching people');
      }
    } catch (error) {
      console.error('ApiPersonRepository.getAll error:', error);
      throw error;
    }
  }


  async getById(id: string): Promise<Person> {
    try {
      const response = await this.httpService.get<ApiSuccessResponse<PersonApiData>>(`/people/${id}`);
      
      if (response.status === 'success') {
        return Person.fromApi(response.data);
      } else {
        throw new Error(`Person with ID ${id} not found`);
      }
    } catch (error) {
      console.error(`ApiPersonRepository.getById(${id}) error:`, error);
      throw error;
    }
  }
}
