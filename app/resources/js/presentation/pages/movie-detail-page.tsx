import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { getFilmByIdUseCase } from '@/main/dependencies-factory';
import { Film } from '@/domain/entities';
import DetailPageLayout from '@/presentation/components/detail-page-layout';
import '@/css/detail-page.css';

const MovieDetailPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [film, setFilm] = useState<Film | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [relatedCharacters, setRelatedCharacters] = useState<Array<{id: string, name?: string}>>([]);

  useEffect(() => {
    const fetchFilm = async () => {
      if (!id) return;
      
      try {
        setIsLoading(true);
        const result = await getFilmByIdUseCase.execute(id);
        setFilm(result);
        
        setRelatedCharacters(result.characters);
      } catch (e) {
        setError(e instanceof Error ? e.message : 'Error fetching movie details');
      } finally {
        setIsLoading(false);
      }
    };

    fetchFilm();
  }, [id]);

  const renderCharacterLinks = () => {
    if (!relatedCharacters || relatedCharacters.length === 0) {
      return <span>No characters</span>;
    }
    
    return relatedCharacters.map((character, index) => (
      <React.Fragment key={character.id}>
        <Link 
          to={`/people/${character.id}`} 
          className="related-item-link inline"
        >
          {character.name}
        </Link>
        {index < relatedCharacters.length - 1 && <span>, </span>}
      </React.Fragment>
    ));
  };

  const renderFilmContent = () => {
    if (!film) return null;
    
    return (
      <>
        <h1 className="detail-title">{film.title}</h1>
        
        <div className="detail-sections">
          <div className="detail-section">
            <h2 className="section-title">Opening Crawl</h2>
            <div className="opening-crawl">
              <p>{film.openingCrawl}</p>
            </div>
          </div>
          
          <div className="detail-section">
            <h2 className="section-title">Characters</h2>
            <div className="related-items-inline">
              {renderCharacterLinks()}
            </div>
          </div>
        </div>
        
        <div className="detail-actions">
          <Link to="/" className="button button-primary">BACK TO SEARCH</Link>
        </div>
      </>
    );
  };

  return (
    <DetailPageLayout 
      isLoading={isLoading}
      error={error}
      loadingMessage="Loading film details..."
      title={film ? film.title : 'Film not found'}
    >
      {renderFilmContent()}
    </DetailPageLayout>
  );
};

export default MovieDetailPage;