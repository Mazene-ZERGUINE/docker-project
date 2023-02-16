import { Component, OnInit } from '@angular/core';
import { Book } from '../shared/book.interface';
import { BooksService } from '../shared/books.service';
import { Observable } from 'rxjs';

@Component({
  selector: 'app-books-list',
  templateUrl: './books-list.component.html',
  styleUrls: ['./books-list.component.scss'],
})
export class BooksListComponent implements OnInit {
  books$?: Observable<Book[]>;

  constructor(private readonly booksService: BooksService) {}

  ngOnInit() {
    this.getBooks();
  }

  getBooks() {
    this.books$ = this.booksService.getAll();
  }
}
