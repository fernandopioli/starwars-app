import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';

import { Person } from '@/domain/entities';
import { getPersonByIdUseCase } from '@/main/dependencies-factory';
import DetailPageLayout from '@/presentation/components/detail-page-layout';
import '@/css/detail-page.css';

const PersonDetailPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [person, setPerson] = useState<Person | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [relatedFilms, setRelatedFilms] = useState<Array<{id: string, name?: string}>>([]);

  useEffect(() => {
    const fetchPerson = async () => {
      if (!id) return;
      
      try {
        setIsLoading(true);
        const result = await getPersonByIdUseCase.execute(id);
        setPerson(result);
        
        setRelatedFilms(result.films);
      } catch (e) {
        setError(e instanceof Error ? e.message : 'Error fetching person details');
      } finally {
        setIsLoading(false);
      }
    };

    fetchPerson();
  }, [id]);

  const renderPersonContent = () => {
    if (!person) return null;
    
    return (
      <>
        <h1 className="detail-title">{person.name}</h1>
        
        <div className="detail-sections">
          <div className="detail-section">
            <h2 className="section-title">Details</h2>
            <div className="detail-attributes">
              <div className="attribute">
                <span className="attribute-label">Birth Year:</span>
                <span className="attribute-value">{person.birthYear}</span>
              </div>
              <div className="attribute">
                <span className="attribute-label">Gender:</span>
                <span className="attribute-value">{person.gender}</span>
              </div>
              <div className="attribute">
                <span className="attribute-label">Eye Color:</span>
                <span className="attribute-value">{person.eyeColor}</span>
              </div>
              <div className="attribute">
                <span className="attribute-label">Hair Color:</span>
                <span className="attribute-value">{person.hairColor}</span>
              </div>
              <div className="attribute">
                <span className="attribute-label">Height:</span>
                <span className="attribute-value">{person.height}</span>
              </div>
              <div className="attribute">
                <span className="attribute-label">Mass:</span>
                <span className="attribute-value">{person.mass}</span>
              </div>
            </div>
          </div>
          
          <div className="detail-section">
            <h2 className="section-title">Movies</h2>
            <div className="related-items">
              {relatedFilms.map(film => {
               
                return (
                  <Link 
                    key={film.id} 
                    to={`/films/${film.id}`}  
                    className="related-item-link"
                  >
                    {film.name}
                  </Link>
                );
              })}
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
      loadingMessage="Loading person details..."
      title={person ? person.name : 'Person not found'}
    >
      {renderPersonContent()}
    </DetailPageLayout>
  );
};

export default PersonDetailPage;