import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { getFilmByIdUseCase } from '@/main/dependencies-factory';
import { Film } from '@/domain/entities';
import '../../../css/detail-page.css';

const MovieDetailPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [film, setFilm] = useState<Film | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchFilm = async () => {
      if (!id) return;
      
      try {
        setIsLoading(true);
        const result = await getFilmByIdUseCase.execute(id);
        setFilm(result);
      } catch (e) {
        setError(e instanceof Error ? e.message : 'Erro ao buscar detalhes do filme');
      } finally {
        setIsLoading(false);
      }
    };

    fetchFilm();
  }, [id]);

  if (isLoading) {
    return (
      <div className="detail-page">
        <div className="detail-loading">
          <p>Carregando...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="detail-page">
        <div className="detail-error">
          <h2>Erro</h2>
          <p>{error}</p>
          <Link to="/" className="back-link">Voltar para a busca</Link>
        </div>
      </div>
    );
  }

  if (!film) {
    return (
      <div className="detail-page">
        <div className="detail-error">
          <h2>Filme não encontrado</h2>
          <Link to="/" className="back-link">Voltar para a busca</Link>
        </div>
      </div>
    );
  }

  return (
    <div className="detail-page">
      <div className="detail-header">
        <Link to="/" className="back-link">Voltar para a busca</Link>
        <h1>{film.title}</h1>
      </div>
      
      <div className="detail-content">
        <div className="detail-section">
          <h2>Informações Gerais</h2>
          <div className="detail-info-grid">
            <div className="detail-info-item">
              <span className="detail-label">Episódio:</span>
              <span className="detail-value">{film.episodeId}</span>
            </div>
            
            <div className="detail-info-item">
              <span className="detail-label">Diretor:</span>
              <span className="detail-value">{film.director}</span>
            </div>
            
            <div className="detail-info-item">
              <span className="detail-label">Produtor:</span>
              <span className="detail-value">{film.producer}</span>
            </div>
            
            <div className="detail-info-item">
              <span className="detail-label">Data de Lançamento:</span>
              <span className="detail-value">{film.getFormattedReleaseDate()}</span>
            </div>
          </div>
        </div>
        
        <div className="detail-section">
          <h2>Abertura</h2>
          <div className="opening-crawl">
            <p>{film.openingCrawl}</p>
          </div>
        </div>
        
        <div className="detail-section">
          <h2>Personagens</h2>
          <p>Implementação dos personagens relacionados virá aqui</p>
        </div>
      </div>
    </div>
  );
};

export default MovieDetailPage;