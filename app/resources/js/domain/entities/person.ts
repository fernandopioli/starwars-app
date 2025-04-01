import { PersonApiData } from './api.types';


export class Person {
  readonly id: string;
  readonly name: string;
  readonly height: string;
  readonly mass: string;
  readonly hairColor: string;
  readonly skinColor: string;
  readonly eyeColor: string;
  readonly birthYear: string;
  readonly gender: string;
  readonly films: string[];

  private constructor({
    id,
    name,
    height,
    mass,
    hairColor,
    skinColor,
    eyeColor,
    birthYear,
    gender,
    films = [],
  }: {
    id: string;
    name: string;
    height: string;
    mass: string;
    hairColor: string;
    skinColor: string;
    eyeColor: string;
    birthYear: string;
    gender: string;
    films?: string[];
  }) {
    this.id = id;
    this.name = name;
    this.height = height;
    this.mass = mass;
    this.hairColor = hairColor;
    this.skinColor = skinColor;
    this.eyeColor = eyeColor;
    this.birthYear = birthYear;
    this.gender = gender;
    this.films = films;
  }

  static fromApi(data: PersonApiData): Person {
    return new Person({
      id: data.id,
      name: data.name,
      height: data.height,
      mass: data.mass,
      hairColor: data.hair_color,
      skinColor: data.skin_color,
      eyeColor: data.eye_color,
      birthYear: data.birth_year,
      gender: data.gender,
      films: data.films || [],
    });
  }


  getFilmIds(): string[] {
    return this.films.map(url => {
      const parts = url.split('/');
      return parts[parts.length - 2]; // Pega o ID da URL
    });
  }

  getFormattedHeight(): string {
    if (!this.height || this.height === 'unknown') {
      return 'Desconhecida';
    }
    
    const heightNum = parseInt(this.height, 10);
    if (isNaN(heightNum)) {
      return this.height;
    }
    
    if (heightNum >= 100) {
      return `${(heightNum / 100).toFixed(2)}m`;
    }
    
    return `${heightNum}cm`;
  }

  getFormattedMass(): string {
    if (!this.mass || this.mass === 'unknown') {
      return 'Desconhecida';
    }
    
    return `${this.mass}kg`;
  }
}

export default Person;