export interface Book {
  isbn: string;
  title: string;
  author: string;
  overview?: string;
  picture?: string;
  read_count?: number;
  created_at: {
    date: string;
    timezone_type: number;
    timezone: string;
  };
  updated_at?: {
    date: string;
    timezone_type: number;
    timezone: string;
  };
}
