import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import { SearchPage, PersonDetailPage, MovieDetailPage } from '@/presentation/pages';

const AppRoutes: React.FC = () => {
  return (
    <Routes>
      <Route path="/" element={<SearchPage />} />
      <Route path="/people/:id" element={<PersonDetailPage />} />
      <Route path="/films/:id" element={<MovieDetailPage />} />
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
};

export default AppRoutes;