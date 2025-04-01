import { PaginatedResult, Person } from "@/domain/entities";
import { PersonRepository } from "@/application/interfaces";


export class GetPeopleUseCase {
  constructor(private readonly repository: PersonRepository) {}


  async execute(query?: string): Promise<PaginatedResult<Person>> {
    try {
      const result = await this.repository.getAll(query);
      
      return result;
    } catch (error) {
      console.error('GetPeopleUseCase error:', error);
      throw error;
    }
  }
}
