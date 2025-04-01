import { HttpService, AxiosHttpService } from "@/infrastructure/http";
import { FilmRepository, PersonRepository } from "@/application/interfaces";
import { ApiFilmRepository, ApiPersonRepository } from "@/infrastructure/repositories";
import { GetFilmsUseCase, GetFilmByIdUseCase, GetPeopleUseCase, GetPersonByIdUseCase } from "@/application/services";

export const httpService: HttpService = new AxiosHttpService('/api/v1');

export const filmRepository: FilmRepository = new ApiFilmRepository(httpService);
export const personRepository: PersonRepository = new ApiPersonRepository(httpService);


export const getFilmsUseCase = new GetFilmsUseCase(filmRepository);
export const getFilmByIdUseCase = new GetFilmByIdUseCase(filmRepository);
export const getPeopleUseCase = new GetPeopleUseCase(personRepository);
export const getPersonByIdUseCase = new GetPersonByIdUseCase(personRepository);