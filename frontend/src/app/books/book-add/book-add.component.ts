import { Component, OnDestroy, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { BooksService } from '../shared/books.service';
import { Router } from '@angular/router';
import { Subject, takeUntil } from 'rxjs';
import { BookDTO } from '../shared/book.interface';
import { ToastService } from '../../shared/components/toast/toast.service';

@Component({
  selector: 'app-book-add',
  templateUrl: './book-add.component.html',
  styleUrls: ['./book-add.component.scss'],
})
export class BookAddComponent implements OnInit, OnDestroy {
  form?: FormGroup;

  private destroy$: Subject<boolean> = new Subject<boolean>();

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
    this.form = this.fb.group({
      title: this.fb.control('', Validators.required),
      author: this.fb.control('', [
        Validators.required,
        Validators.minLength(1),
      ]),
      overview: this.fb.control(''),
      read_count: this.fb.control(1),
      isbn: this.fb.control('', [
        Validators.required,
        Validators.minLength(1),
        Validators.maxLength(13),
      ]),
    });
  }

  onSubmit(): void {
    if (this.form?.invalid) return;

    const { read_count } = this.form?.value;
    const readCountValue: number =
      Object.is(NaN, Number(read_count)) || Number(read_count) <= 0
        ? 1
        : Number(read_count);

    const book: BookDTO = {
      ...this.form?.value,
      read_count: readCountValue,
    };

    this.booksService
      .create(book)
      .pipe(takeUntil(this.destroy$))
      .subscribe((res: any) => {
        const isClientHttpError: boolean =
          res?.response_code >= 400 && res?.response_code < 500;

        if (isClientHttpError) {
          this.showToast('Erreur', 'Le ISBN existe déjà.');
          return;
        }
        if (res?.response_code >= 500) {
          this.showToast('Erreur', "Une erreur s'est produite.");
          return;
        }

        this.showToast('Confirmation', 'Le livre a été ajouté.');
        this.router.navigateByUrl('/');
      });
  }

  showToast(header: string, message: string) {
    this.toastService.show(header, message);
  }

  ngOnDestroy(): void {
    this.destroy$.next(true);
    this.destroy$.unsubscribe();
  }
}
