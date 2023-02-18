import { Component } from '@angular/core';
import { UntilDestroy, untilDestroyed } from '@ngneat/until-destroy';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { BookDTO } from '../shared/book.interface';
import { BooksService } from '../shared/books.service';
import { Router } from '@angular/router';
import { ToastService } from '../../shared/components/toast/toast.service';

@UntilDestroy()
@Component({
  selector: 'app-book-add',
  templateUrl: './book-add.component.html',
  styleUrls: ['./book-add.component.scss'],
})
export class BookAddComponent {
  form?: FormGroup;

  constructor(
    private readonly booksService: BooksService,
    private readonly fb: FormBuilder,
    private readonly router: Router,
    private readonly toastService: ToastService
  ) {}

  ngOnInit(): void {
    this.initForm();
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

    this.createBook(book);
  }

  private createBook(book: BookDTO): void {
    this.booksService
      .create(book)
      .pipe(untilDestroyed(this))
      .subscribe((res: any) => {
        if (res?.response_code >= 500) {
          this.showToast('Erreur', "Une erreur s'est produite.");
          return;
        }
        if (res?.response_code >= 400) {
          this.showToast('Erreur', 'Le ISBN existe déjà.');
          return;
        }

        this.showToast('Confirmation', 'Le livre a été ajouté.');
        this.router.navigateByUrl(`/books/${book.isbn}`);
      });
  }

  private isReadCountInputValid(value: string | number): boolean {
    return Object.is(NaN, Number(value)) || Number(value) <= 0;
  }

  private showToast(header: string, message: string) {
    this.toastService.show(header, message);
  }
}
