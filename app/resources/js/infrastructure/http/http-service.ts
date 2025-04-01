export interface HttpService {
    get<T>(endpoint: string, params?: Record<string, string>): Promise<T>;
  }