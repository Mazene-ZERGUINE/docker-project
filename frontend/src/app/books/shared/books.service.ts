import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map, Observable } from 'rxjs';
import { Book } from './book.interface';

const API_URL = 'http://localhost:8000/api';

@Injectable({
  providedIn: 'root',
})
export class BooksService {
  private readonly apiUrl: string;

  constructor(private readonly http: HttpClient) {
    this.apiUrl = API_URL;
  }

  getAll(): Observable<Book[]> {
    return this.http
      .get<Book[]>(`${this.apiUrl}/books`)
      .pipe(map((res: any) => res.data));
  }
}
