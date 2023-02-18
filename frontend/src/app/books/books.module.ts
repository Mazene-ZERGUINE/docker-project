import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { BooksRoutingModule } from './books-routing.module';
import { BooksComponent } from './books.component';
import { BooksListComponent } from './books-list/books-list.component';
import { ComponentsModule } from '../shared/components/components.module';
import { BookAddComponent } from './book-add/book-add.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ToastModule } from '../shared/components/toast/toast.module';
import { BookDetailsComponent } from './book-details/book-details.component';

@NgModule({
  declarations: [
    BooksComponent,
    BooksListComponent,
    BookAddComponent,
    BookDetailsComponent,
  ],
  exports: [BooksComponent],
  imports: [
    CommonModule,
    BooksRoutingModule,
    ComponentsModule,
    FormsModule,
    ReactiveFormsModule,
    ToastModule,
  ],
})
export class BooksModule {}
