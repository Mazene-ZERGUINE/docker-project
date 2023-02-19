import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { BooksService } from '../shared/books.service';
import { ActivatedRoute, Router } from '@angular/router';
import { ToastService } from '../../shared/components/toast/toast.service';
import { BookDTO } from '../shared/book.interface';
import { UntilDestroy, untilDestroyed } from '@ngneat/until-destroy';

@UntilDestroy()
@Component({
  selector: 'app-book-edit',
  templateUrl: './book-edit.component.html',
  styleUrls: ['./book-edit.component.scss'],
})
export class BookEditComponent {
  book?: BookDTO;
  form?: FormGroup;

  constructor(
    private readonly booksService: BooksService,
    private readonly fb: FormBuilder,
    private readonly route: ActivatedRoute,
    private readonly router: Router,
    private readonly toastService: ToastService
  ) {}

  ngOnInit(): void {
    this.initForm();
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
          this.showToast('Erreur', "Le livre n'existe pas. get");
          this.redirectToNotFoundPage();
          return;
        }

        this.setFormValues(res?.data);
      });
  }

  setFormValues(book: BookDTO): void {
    const { isbn, title, author, overview, read_count } = book;

    this.form?.patchValue({
      isbn,
      title,
      author,
      overview,
      read_count,
    });
  }

  initForm(): void {
    const integerRegex = /^[0-9]+$/;

    this.form = this.fb.group({
      title: this.fb.control('', Validators.required),
      author: this.fb.control('', [
        Validators.required,
        Validators.minLength(1),
      ]),
      overview: this.fb.control(''),
      read_count: this.fb.control(null, Validators.pattern(integerRegex)),
      isbn: this.fb.control('', [
        Validators.required,
        Validators.pattern(integerRegex),
        Validators.minLength(1),
        Validators.maxLength(13),
      ]),
    });
  }

  onFocusOut(): void {
    if (this.form?.get('read_count')?.value == null) {
      this.form?.get('read_count')?.patchValue(1);
    }
  }

  onSubmit(): void {
    if (this.form?.invalid) return;

    const { read_count } = this.form?.value;
    const readCountValue: number = this.isReadCountInputValid(read_count)
      ? 1
      : Number(read_count);

    const book: BookDTO = {
      ...this.form?.value,
      read_count: readCountValue,
    };

    this.updateBook(book.isbn, book);
  }

  updateBook(isbn: string, book: BookDTO): void {
    this.booksService
      .update(isbn, book)
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

        this.showToast('Information', 'Le livre a été modifié.');
        this.router.navigateByUrl(`/books/${book.isbn}`);
      });
  }

  private isReadCountInputValid(value: string | number): boolean {
    return Object.is(NaN, Number(value)) || Number(value) <= 0;
  }

  private redirectToNotFoundPage(): void {
    this.router.navigateByUrl('**', { skipLocationChange: true });
  }

  private showToast(header: string, message: string): void {
    this.toastService.show(header, message);
  }
}
