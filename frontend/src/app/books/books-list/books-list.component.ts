import { Component, OnInit } from '@angular/core';
import { Book } from '../shared/book.interface';
import { BooksService } from '../shared/books.service';
import { Observable } from 'rxjs';
import { UntilDestroy, untilDestroyed } from '@ngneat/until-destroy';
import { ToastService } from '../../shared/components/toast/toast.service';
import { Router } from '@angular/router';

@UntilDestroy()
@Component({
  selector: 'app-books-list',
  templateUrl: './books-list.component.html',
  styleUrls: ['./books-list.component.scss'],
})
export class BooksListComponent implements OnInit {
  books$?: Observable<Book[]>;

  constructor(
    private readonly booksService: BooksService,
    private readonly router: Router,
    private readonly toastService: ToastService
  ) {}

  ngOnInit() {
    this.getBooks();
  }

  getBooks() {
    this.books$ = this.booksService.getAll();
  }

  deleteBook(isbn: string): void {
    this.booksService
      .delete(isbn)
      .pipe(untilDestroyed(this))
      .subscribe((res) => {
        if (res?.response_code >= 500) {
          this.showToast('Erreur', "Une erreur s'est produite.");
          return;
        }
        if (res?.response_code >= 400) {
          this.showToast('Erreur', "Le livre n'existe pas.");
          this.redirectToNotFoundPage();
          return;
        }

        this.showToast('Confirmation', 'Le livre a été supprimé.');
        this.getBooks();
      });
  }

  trackByIsbn(index: number, book: Book) {
    return book.isbn;
  }

  private redirectToNotFoundPage(): void {
    this.router.navigateByUrl('**', { skipLocationChange: true });
  }

  private showToast(header: string, message: string): void {
    this.toastService.show(header, message);
  }
}
