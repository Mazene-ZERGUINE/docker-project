import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { BooksComponent } from './books.component';
import { BooksListComponent } from './books-list/books-list.component';
import { BookAddComponent } from './book-add/book-add.component';

const routes: Routes = [
  {
    path: '',
    component: BooksComponent,
    children: [
      {
        path: '',
        component: BooksListComponent,
      },
    ],
  },
  {
    path: 'books',
    component: BooksComponent,
    children: [
      {
        path: 'new',
        component: BookAddComponent,
      },
    ],
  },
  {
    path: '**',
    redirectTo: '',
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class BooksRoutingModule {}
