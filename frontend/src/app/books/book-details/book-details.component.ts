import { Component, OnInit } from '@angular/core';
import { BooksService } from '../shared/books.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Book } from '../shared/book.interface';
import { UntilDestroy, untilDestroyed } from '@ngneat/until-destroy';
import { ToastService } from 'src/app/shared/components/toast/toast.service';

@UntilDestroy()
@Component({
  selector: 'app-book-details',
  templateUrl: './book-details.component.html',
  styleUrls: ['./book-details.component.scss'],
})
export class BookDetailsComponent implements OnInit {
  book?: Book;

  constructor(
    private readonly booksService: BooksService,
    private readonly route: ActivatedRoute,
    private readonly router: Router,
    private readonly toastService: ToastService
  ) {}

  ngOnInit(): void {
    this.subscribeToParamMap();
  }

  private subscribeToParamMap(): void {
    this.route.paramMap.subscribe((params: any) => {
      const { isbn } = params?.params;
      const isParamInvalid = Object.is(NaN, Number(isbn)) || isbn <= 0;
      if (isParamInvalid) {
        this.redirectToNotFoundPage();
      }

      this.getBookByIsbn(isbn);
    });
  }

  private getBookByIsbn(isbn: string): void {
    this.booksService
      .getByIsbn(isbn)
      .pipe(untilDestroyed(this))
      .subscribe((res) => {
        if (res?.response_code >= 500) {
          this.showToast('Erreur', "Une erreur s'est produite.");
          return;
        }
        if (res?.response_code >= 400) {
          this.showToast('Erreur', "Le livre n'existe pas.");
          this.redirectToNotFoundPage();
        }

        this.book = res?.data;
      });
  }

  private redirectToNotFoundPage(): void {
    this.router.navigateByUrl('**', { skipLocationChange: true });
  }

  private showToast(header: string, message: string): void {
    this.toastService.show(header, message);
  }
}
