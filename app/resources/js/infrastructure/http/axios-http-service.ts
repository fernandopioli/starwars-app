import axios, { AxiosInstance } from 'axios';
import { HttpService } from './http-service';

export class AxiosHttpService implements HttpService {
  private readonly api: AxiosInstance;
  
  constructor(baseURL: string) {
    this.api = axios.create({
      baseURL,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    });
  }

  async get<T>(endpoint: string, params?: Record<string, string>): Promise<T> {
    try {
      const response = await this.api.get<T>(endpoint, { params });
      return response.data;
    } catch (error) {
      console.error(`GET request failed for ${endpoint}:`, error);
      throw error;
    }
  }
}

// export const httpService = new AxiosHttpService('/api/v1');