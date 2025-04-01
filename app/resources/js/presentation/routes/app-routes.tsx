import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import SearchPage from '../pages/search-page';
import PersonDetailPage from '../pages/person-detail-page';
import MovieDetailPage from '../pages/movie-detail-page';

const AppRoutes: React.FC = () => {
  return (
    <Routes>
      <Route path="/" element={<SearchPage />} />
      <Route path="/people/:id" element={<PersonDetailPage />} />
      <Route path="/movies/:id" element={<MovieDetailPage />} />
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
};

export default AppRoutes;