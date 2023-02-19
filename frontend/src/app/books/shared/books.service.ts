import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map, Observable } from 'rxjs';
import { Book, BookDTO } from './book.interface';

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

  getByIsbn(isbn: string): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/book/${isbn}`);
  }

  create(book: BookDTO): Observable<any> {
    return this.http.post(`${this.apiUrl}/book`, book);
  }

  update(isbn: string, book: BookDTO): Observable<any> {
    return this.http.patch(`${this.apiUrl}/book/${isbn}/edit`, book);
  }

  delete(isbn: string): Observable<any> {
    return this.http.delete(`${this.apiUrl}/book/${isbn}/delete`);
  }
}
