import { PersonRepository } from "@/application/interfaces";
import { Person } from "@/domain/entities";

export class GetPersonByIdUseCase {
    constructor(private readonly repository: PersonRepository) {}

    async execute(id: string): Promise<Person> {
      try {
        const personData = await this.repository.getById(id);
        return personData;
      } catch (error) {
        console.error(`GetPersonByIdUseCase error:`, error);
        throw error;
      }
    }
  }